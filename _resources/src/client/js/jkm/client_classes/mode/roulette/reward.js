class ModeRouletteReward extends ModeRoulette{

	get loopTimeA(){return 3};

	constructor(parent){
		super(parent);
		this.setupSe();
	}
	setupSe(){
		let url = "_resources/src/client/sound/osabisi.sakura.ne.jp/fixed/reward.mp3";
		this.se = new Sound(url);
	}
	start(){
		let parent = this.parent;

		this.se.play();
		
		//ルーレットのセルに対応するルートテーブルの値を参照
		let bonus = this.rootTable[ parent.game.roulette_cell ];
		//コインの払い出し
		parent.game.coin += bonus;
		//演出タイマーの初期化
		parent.globalTimer.reset();
		
		//演出の初期化
		parent.game.updatePlayerHandRSP(false);
		
		//報酬獲得状態に移行
		parent.game.scene = "reward";
	}
	main(){
		let parent = this.parent;
		this.drawEffect();

	}

	drawEffect(){
		//略称付与
		let parent = this.parent;
		let gTimer = parent.globalTimer;
		let cycleA = this.loopTimeA;
		//条件と実行内容を列挙するA
		let triggerA  = CountTrigger.isFireTime(cycleA,gTimer.count);
		let updateA   = function(){
			let count = CountTrigger.FireTimes(cycleA,gTimer.count);
			let light = Boolean(count % 2);
			if(light) parent.game.updateRouletteSingle(parent.game.roulette_cell);
			else	  parent.game.updateRouletteOff();
		};
		//条件と実行内容を列挙するForFinish
		let finishTrigger = (gTimer.count > 60);
		let intoFinish    = ()=>{this.finish()};
		//まとめて詰める
		let bullets = [
			[triggerA,		updateA],
			[finishTrigger,	intoFinish]
		];
		//全部放つ
		TaskTrigger.fire(bullets);
	}
	finish(){
		//略称定義
		let parent = this.parent;
		
		parent.modes.repeat.start();
	}
}