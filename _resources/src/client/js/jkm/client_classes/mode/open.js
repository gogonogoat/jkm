class ModeOpen extends Mode{
	constructor(parent){super(parent);}
	start(){
		//家族構成
		let self    = this;
		let parent  = this.parent;
		//エラー文
		let errorA  = function(){console.log("load failed_A");};
		let errorB  = function(){console.log("load failed_B");};
		//XMLリクエスト作成
		let request = new XMLHttpRequest();
		request.open('GET', '../jkm/load.php', true);
		request.onerror = errorA;
		request.onload = function(){
			let isSuccess = HttpStatus.isSuccess(request.status);
			let coin = request.response;
			if(isSuccess) self.finish(coin);
			else errorB();
		};
		request.send();
		//モード変更
		parent.game.scene	= "open";
	}
	finish(coin){
		
		console.log("coin is " + coin);
		console.log("load ok");
		this.parent.game.coin = coin;
		this.parent.modes.wait.start(parent);
	}
}