/*==============================
Desc:
ゲームを終えて周回を行う際に必要な処理を行うクラス
	やっていること
	・サーバーへクライアント内のコインデータをセーブ
*/
class ModeRepeat extends Mode{
	constructor(parent){super(parent);}
	start(){
		//家族構成
		let self     = this;
		let parent   = this.parent;
		//エラー文
		let errorA   = function(){console.log("load failed_A");};
		let errorB   = function(){console.log("load failed_B");};
		//サーバーへPOSTするデータ
		let postData = 'user_coin=' + parent.game.coin;
		console.log("coin is " + parent.game.coin);
		//XMLリクエスト作成
		let request = new XMLHttpRequest();
		request.open('POST', '../jkm/save.php', true);
		request.setRequestHeader(
			'content-type',
			'application/x-www-form-urlencoded;charset=UTF-8'
		);
		request.onerror = errorA;
		request.onload = function(){
			let isSuccess = HttpStatus.isSuccess(request.status);
			if(isSuccess) self.finish();
			else errorB();
		};
		request.send(postData);
		//モード変更
		parent.game.scene	= "repeat";
	}
	finish(){
		console.log("save ok");
		this.parent.modes.wait.start();
	}
}