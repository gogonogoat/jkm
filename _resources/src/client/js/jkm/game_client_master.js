class GameClientMaster{
	constructor(app){
		//略称付与
		let s = this;
		//UIであるVueクラス
		s.game = app;
		//ゲームのFPS(※近似値)
		s.defaultFps = 30;
		//全ての手札
		s.cards = ["rock","scissors","papar"];
		//演出用タイマーカウンター関連
		s.totalTimer	= new Counter();	//総合タイマー
		s.globalTimer	= new Counter();	//グローバルタイマー
		s.fluctuate		= new Counter();	//演出開始点の揺らぎ用
		
		s.coinMonitor   = new CoinMonitor(s,0);
		
		//メインループ実行間隔の定義
		s.setupInterval(s);
		//モード・シーンの初期化
		s.setupModes(s);
		s.setupScenes(s);
		//UIの初期化
		s.game.init();
	}
	main(){
		//自己言及
		let self = this;
		//メインループをコールバックに指定
		let callback = function(){self.sceneLoop(self)};
		//インターバル毎にコールバック実行
		setInterval( callback, self.interval);
		//ゲームモード：初期状態を開始
		this.modes.open.start();
	}
	//メインループの実行間隔を定義
	setupInterval(self){
		//指定FPSへ寄せるに必要なインターバル近似値を得る
		self.interval = Math.floor( 1000 / self.defaultFps );
	}
	//各モードを司るクラスのインスタンス作成
	setupModes(self){
		let modes = {
			open:		ModeOpen,
			repeat:		ModeRepeat,
			wait: 		ModeWait,
			battle: 	ModeBattle,
			win:		ModeResultWin,
			draw:		ModeResultDraw,
			lose:		ModeResultLose,
			chose:		ModeRouletteChose,
			reward:		ModeRouletteReward
		};
		self.modes ={};
		let modesSet = function(v){
			let propName = v[0];
			let className = v[1];
			self.modes[propName] = new className(self);
		};
		Object.entries(modes).forEach(modesSet);
	}
	//シーン毎に呼び出すモードを定義
	setupScenes(self){
		let m = self.modes;
		self.scenes = {
			open:		m.open,
			repeat:		m.repeat,
			wait:		m.wait,
			battle:		m.battle,
			win:		m.win,
			draw:		m.draw,
			lose:		m.lose,
			chose:		m.chose,
			reward:		m.reward
		};
	}
	sceneLoop(self){
		//略称付与
		let scenes	 = self.scenes;
		let scene	 = self.game.scene;
		//設定されているシーン名はシーンリストに存在するか
		let isEnable = (scene in scenes);
		//上記を満たすならば該当のシーンの処理を実行する
		if(isEnable) scenes[scene].main();
		//コインモニターを更新する
		this.coinMonitor.update();
		//このフレームの入力受付を終了する
		self.game.closeMessage();
		//タイマー進行
		self.continueTimer(self);
	}
	//タイマー進行
	continueTimer(self){
		self.globalTimer.next();
		self.totalTimer.next();
	}
	//対処すべき入力の有無を取得
	isUntreatedInput(self,vals){
		//UIへの入力値とメソッドへの入力値が一致するかを得る
		let isIncludes = function(){
			//入力値は配列等のオブジェクトか
			let isObject = (typeof(vals) == "object");
			//非オブジェクト値は配列に入れてオブジェクト化する
			let obj = isObject ? vals : [vals];
			//オブジェクト内に想定値が含まれるか
			let includes = obj.includes(self.game.message);
			return includes;
		};
		//入力に対して未処理の場合のみ上記関数の真偽を問う
		let result = self.game.recepted ? false : isIncludes();
		return result;
	}
}