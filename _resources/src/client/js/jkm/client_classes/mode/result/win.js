class ModeResultWin extends ModeResult{
	get loopTimeA(){return 8;}
	get loopTimeB(){return 2;}
	
	constructor(parent){
		super(parent);
		this.setupSe();
	}
	setupSe(){
		let url = "_resources/src/client/sound/dova-s.jp/causality_sound/fixed/finish.mp3";
		this.se = new Sound(url);
	}
	start(){
		let parent = this.parent;
		let game = parent.game;
		
		
		//バトルリザルト更新
		game.battle_result = this.results[0];
		
		//グローバルタイマーリセット
		parent.globalTimer.reset();
		
		//効果音再生
		this.se.play();
		
		this.holdHand = this.loseHand(parent,game.p_hand);
		this.holdIndex = this.handToIndex(parent,this.holdHand);
		game.updateEnemyHand(this.holdIndex);
		game.updateResultLumpW(true);
		
		parent.game.scene = "win";
	}
	main(){
		let parent = this.parent;
		this.drawEffect();
	}

	drawEffect(){
		let self = this;
		let parent = this.parent
		let game = parent.game;
		//略称付与
		let gTimer = parent.globalTimer.count;
		let cycleA = this.loopTimeA;
		let cycleB = this.loopTimeB;

		//点滅の実行
		let triggerA = CountTrigger.isFireTime(cycleA, gTimer);
		let updateA = ( ()=>self.handBlink(cycleA, gTimer) );
		
		let triggerB = CountTrigger.isFireTime(cycleB, gTimer);
		
		let updateB = ()=>{
			let counter = CountTrigger.FireTimes(cycleB, gTimer);
			game.updateRouletteQuad(counter);
			/*
				ここで初回表示時に描画が盛大に狂ったように見えるのは
				処理がおかしいのではなくアセットのローディングの問題。
				画像のプリロード入れてから実行すれば問題は起こらない。
			*/
		};
		
		//終了への移行
		let finishTrigger = ( gTimer >= 60 );
		let intoFinish = ( ()=>self.finish() );
		
		//まとめて実行
		let bullets = [
			[ triggerA,			updateA ],
			[ triggerB,			updateB ],
			[ finishTrigger,	intoFinish ]
		];
		TaskTrigger.fire(bullets);
	}
	finish(){
		//略称定義
		let parent = this.parent;
		//演出終了
		//Draw,Loseとは異なり敵の負けた手はそのまま晒す
		//Winランプもルーレットが終わるまで出しっぱなし
		parent.game.updateEnemyHand(this.holdIndex);
		
		//ルーレット準備状態に移行する
		parent.modes.chose.start();
	}
}