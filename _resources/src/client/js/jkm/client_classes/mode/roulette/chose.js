class ModeRouletteChose extends ModeRoulette{

	get loopTime(){return 5;}
	constructor(parent){
		super(parent);
		//1セル辺りの待機時間を定義
		this.setupTimeMass();
		//効果音取得
		this.setupSe();
	}
	//ルーレットの1セル移動辺りのディレイを定義
	setupTimeMass(){
		this.timeMass = this.parent.interval * this.rouletteSpeed;
	}
	
	setupSe(){
		let url = "_resources/src/client/sound/osabisi.sakura.ne.jp/fixed/roulette_roll_long.mp3";
		let repeat = true;
		this.se = new Sound(url,repeat);
	}
	
	start(){
		//略称付与
		let parent = this.parent;
		//効果音鳴らす
		this.se.play();
		
		let fluction = function(){
			let margin  = 1;
			let divide  = 4;
			let pattern = Random.intZeroToMax(divide);
			let result  = pattern * divide + margin;
			return result;
		};
		//上記関数でルーレットの揺らぎを定義
		this.rouletteFluction	= fluction();
		//現時刻をミリ秒で取得
		this.rouletteTimeStart	= new Date().getTime();
		//プロパティ名だけ定義しておく
		this.rouletteTimeFinish	= null;
		//前フレームのルーレット点灯位置を初期化
		this.prevCell = -1;
		
		
		//演出の初期化
		parent.game.updatePlayerHandRSP(false);
		
		//ルーレット状態に移行
		parent.game.scene = "chose";
	}
	main(){
		let parent = this.parent;
		
		this.updatePoint();
		this.drawEffect();
		
		//RSP押下で終了
		let trigger = parent.isUntreatedInput(parent,parent.cards);
		if(trigger) this.finish();
	}

	drawEffect(){
		//略称
		let parent = this.parent;
		let game   = parent.game;
		let point  = game.roulette_cell;
		let gTimer = parent.globalTimer.count;
		let cycleB = this.loopTime;
		
		//更新の基準・動作
		let triggerA = (this.prevCell != point);
		let updateA  = ()=>{game.updateRouletteSingle(point)};
		let triggerB = CountTrigger.isFireTime(cycleB, gTimer);
		let updateB  = function(){
			//周期が来た回数
			let counter = CountTrigger.FireTimes(cycleB, gTimer);
			//BETボタン点灯状態
			let light = Boolean(counter % 2);
			//クラス更新
			parent.game.updatePlayerHandRSP(light);
		};
		//まとめてぶっぱ
		let bullets = [
			[triggerA,updateA],
			[triggerB,updateB]
		];
		TaskTrigger.fire(bullets);
		//if(triggerA) updateA();
	}
	//ルーレットの指定地を更新
	updatePoint(){
		let game = this.parent.game;
		//現時刻を取得
		let now = new Date().getTime();
		//開始時刻からの経過時間を取得
		let gap = now - this.rouletteTimeStart;
		//ルーレットの進行度を取得
		let left = Math.floor( gap / this.timeMass );
		//上記を揺らぎを含めた値にする
		let left_ = left + this.rouletteFluction;
		//ルーレット更新後の地点を取得
		let point = ( left_ % this.rootTable.length );
		//前フレームの地点を保存
		this.prevCell = game.roulette_cell;
		//ルーレットの指定地を更新する
		game.roulette_cell = point;
	}
	
	finish(){
		//SE停止
		this.se.pause();
		//報酬獲得へ移行
		this.parent.modes.reward.start();
	}
}