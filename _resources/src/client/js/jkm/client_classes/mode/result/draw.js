class ModeResultDraw extends ModeResult{
	get loopTimeA(){return 8;}
	
	constructor(parent){
		super(parent);
		this.setupSe();
	}
	setupSe(){
		let url = "_resources/src/client/sound/dova-s.jp/causality_sound/fixed/funny.mp3";
		this.se = new Sound(url);
	}
	start(){
		let parent = this.parent;
		let game = parent.game;
		//バトルリザルト更新
		game.battle_result = this.results[1];
		
		//グローバルタイマーリセット
		parent.globalTimer.reset();
		
		this.se.play();
		
		this.holdHand = parent.game.p_hand;
		this.holdIndex = this.handToIndex(parent,this.holdHand);
		game.updateEnemyHand(this.holdIndex);
		game.updateResultLumpD(true);
		//==============================
		//メインループの実行
		game.scene = "draw";
		
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

		//点滅の実行
		let triggerA = CountTrigger.isFireTime(cycleA, gTimer);
		let updateA = ( ()=>self.handBlink(cycleA, gTimer) );
		
		//終了への移行
		let finishTrigger = ( gTimer >= 40 );
		let intoFinish = ( ()=>self.finish() );
		
		//まとめて実行
		let bullets = [
			[ triggerA,			updateA ],
			[ finishTrigger,	intoFinish ]
		];
		TaskTrigger.fire(bullets);
	}
	finish(){
		//略称定義
		let parent = this.parent;
		//演出終了
		parent.game.updateEnemyHand(3);
		parent.game.updateResultLumpD(false);
		
		parent.modes.battle.reduce();
	}
}