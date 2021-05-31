class CoinMonitor{
	//nフレームにつき1回更新
	get updateSpeed(){
		let n = 0.5
		return n;
	}

	constructor(parent,coin){
		this.parent = parent;
		this.prevView = coin;
		this.viewValue = coin;
		this.lastUpdate = parent.totalTimer.count;
	}
	
	//外観を更新する
	update(){
		//略称付与
		let parent   = this.parent ;
		let now		 = parent.totalTimer.count;
		let leftTime = now - this.lastUpdate;
		//前回の表示状態を更新
		this.prevView = this.viewValue;
		//内部値を更新すべき回数を取得
		let leftCount = Math.floor(leftTime / this.updateSpeed);
		//上記回数分だけ内部値の更新動作を実行
		this.approachTimes(leftCount);
		//内部値の変更の有無を取得
		let isChanged = (this.prevView != this.viewValue);
		//変更があるならばGUIの更新を行う
		if(isChanged) this.updateGui();
		//最終更新時刻の更新
		this.lastUpdate = this.parent.totalTimer.count;
	}
	
	updateGui(){
		let coin = this.viewValue;
		this.parent.game.updateCoin(coin);
	}
	//下記を指定回数まとめて行う
	approachTimes(times){
		let f = ()=>{this.approach()};
		[...Array(times)].forEach(f);
	}
	//表示コイン数を実際のコイン数に寄せる
	approach(){
		//略称付与
		let target	= this.parent.game.coin;
		let now		= this.viewValue;
		//条件と命令を列挙
		let more	= (target > now);
		let less	= (target < now);
		let add		= ()=>{this.add()};
		let remove	= ()=>{this.remove()};
		//上記をまとめる
		let bullets = [
			[more, add],
			[less, remove]
		];
		//上記を全て条件の元に実行する
		TaskTrigger.fire(bullets);
	}
	
	add(){this.viewValue++;}
	remove(){this.viewValue--;}
	
	
	
}