/*
Desc:
Note:
	[1]
		設計当初は最初とあいこでSEを別にする予定だったが
		ボイスSEを不採用にした結果
		同じ音を割り当てる方が収まりが良い様になった。
		
		何かの機会に区別の必要があるかもしれないので
		start/reduceの両メソッドを残すことにする。
*/
class ModeBattle extends Mode{
	get loopTimeA(){return 5;}
	get loopTimeB(){return 15;}
	constructor(parent){
		super(parent);
		//略称
		let m = parent.mode;
		//前回の勝敗の初期値
		this.prevResult = -1;
		//勝敗の付け方を選択
		this.judgeType = 0;
		//効果音設定
		this.setupSe();
	}
	setupSe(){
		let url = "_resources/src/client/sound/dova-s.jp/causality_sound/fixed/dram.mp3";
		let repeat = true;
		this.se = new Sound(url,repeat);
	}
	//じゃんけん開始・あいこ 参照:Note [1]
	start() {this.commonProcess();}
	reduce(){this.commonProcess();}

	//じゃんけん開始時・あいこ時共通処理
	commonProcess(){
		//略称
		let parent = this.parent;
		//演出変数初期化
		parent.globalTimer.reset();
		parent.fluctuate.set( Random.intZeroToMax(2) );
		//演出状態初期化
		parent.game.updatePlayerHandB(false);
		parent.game.updatePlayerHandRSP(true);
		//効果音再生
		this.se.play();
		//モード進行
		parent.game.scene = "battle";
	}
	main(){
		let parent = this.parent;
		this.drawEffect();
		//RSPボタンを押下
		let trigger = parent.isUntreatedInput(parent, parent.cards);
		//上記を満たすならば終了処理
		if(trigger) this.finish();
	}
	drawEffect(){
		let parent = this.parent
		//略称付与
		let gTimer = parent.globalTimer.count;
		let cycleA = this.loopTimeA;
		let cycleB = this.loopTimeB;
		
		//更新判定
		let triggerA = CountTrigger.isFireTime(cycleA, gTimer);
		let triggerB = CountTrigger.isFireTime(cycleB, gTimer);
		//更新動作
		let updateA = function(){
			//周期が来た回数
			let counter = CountTrigger.FireTimes(cycleA, gTimer);
			//ディスプレイに表示する手の種類
			let hand = ( parent.fluctuate.count + counter ) % 3;
			//クラス更新
			parent.game.updateEnemyHand(hand);
		};
		let updateB = function(){
			//周期が来た回数
			let counter = CountTrigger.FireTimes(cycleB, gTimer);
			//BETボタン点灯状態
			let light = ((counter+1) % 2);
			//クラス更新
			parent.game.updatePlayerHandRSP(light);
		};
		//まとめて実行
		let bullets = [
			[triggerA, updateA],
			[triggerB, updateB]
		];
		TaskTrigger.fire(bullets);
	}
	finish(){
		let parent = this.parent;
		let g = parent.game;
		let m = parent.modes;
		//プレイヤーの入力値を記憶
		g.p_hand = g.message;
		
		//勝敗結果を司るクラス一覧
		let road = [m.win, m.draw, m.lose];
		//取りうる結果の数を取得
		let waynum = road.length;
		//結果の判定を行う
		let way = this.judge();//Random.intZeroToUnderMax(waynum);
		//戦闘結果を更新
		this.prevResult = way;
		//結果に応じたモードの開始処理へ進む
		road[way].start();
		//効果音を停止
		this.se.pause();
	}
	judge(){
		//略称
		let s = this;
		let idx = this.judgeType;
		//処理をラッピング
		let loop   = ()=>s.judgeLoop(s);
		let random = ()=>s.judgeRandom();
		//指定された処理を実行
		switch(idx){
			case  1:return loop();
			default:return random();
		}
	}
	judgeRandom(){return Random.intZeroToUnderMax(3);}
	judgeLoop(self){
		let p = self.prevResult;
		let result = (p<0) ? 0 : ((p+1)%3);
		return result;
	}
}