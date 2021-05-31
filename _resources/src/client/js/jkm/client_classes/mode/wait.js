class ModeWait extends Mode{
	//現仕様のクラスは静的な値を持つことができないので固定値を返すメソッドで代替する
	get loopTime(){return 30;}
	
	constructor(parent){super(parent);}
	start(){
		let parent = this.parent;
		let game = parent.game;
		
		//演出変数初期化
		parent.globalTimer.reset();
		parent.fluctuate.set( Random.intZeroToMax(2) );
		//演出状態初期化
		//各種ランプを消す
		let lights = [
			game.updatePlayerHandB,
			game.updatePlayerHandR,
			game.updatePlayerHandS,
			game.updatePlayerHandP,
			game.updateResultLumpW,
			game.updateResultLumpD,
			game.updateResultLumpL
		];
		let off = (v=>{v(false)});
		lights.forEach(off);
		
		game.updateEnemyHand(parent.fluctuate.count);
		
		//モード変更
		parent.game.scene	= "wait";
	}
	main(){
		let parent = this.parent;
		this.drawEffect();
		//ベットボタン押下に対して未処理
		let trigger = parent.isUntreatedInput(parent,"bet");
		//上記を満たすならばFinish実行
		if(trigger) this.finish();
	}

	drawEffect(){
		//console.log("@");
		let parent = this.parent;
		//略称付与
		let gTimer = parent.globalTimer.count;
		let cycleA = this.loopTime;
		//更新判定
		let trigger = CountTrigger.isFireTime(cycleA, gTimer);
		//更新動作
		let update = function(){
			//周期が来た回数
			let counter = CountTrigger.FireTimes(cycleA, gTimer);
			//ディスプレイに表示する手の種類
			let hand = ( parent.fluctuate.count + counter ) % parent.cards.length;
			//BETボタン点灯状態
			let light = Boolean(counter % 2);
			//クラス更新
			parent.game.updateEnemyHand(hand);
			parent.game.updatePlayerHandB(light);
		};
		//周期毎に更新を実行
		if(trigger) update();
	}
	finish(){
		//略称定義
		let parent = this.parent;
		let game = parent.game;
		//手持ちのコインはあるか
		let rich = ( game.coin > 0 );
		//上記より支払い金額を取得
		let charge = rich ? 1:0;
		//支払いを実行する
		game.coin -= charge;
		//バトルモード開始準備
		parent.modes.battle.start(parent);
	}
}