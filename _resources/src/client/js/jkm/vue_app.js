var vueApp = new Vue({
	el:'#application',
	data:{
		scene			: '',
		battle_result	: '',
		e_hand			: '',
		p_hand			: '',
		message			: '',
		recepted		: '',
		coin			: 0,
		roulette_cell	: 0,

		classCoinD_100		: "",	// コイン表示100の桁
		classCoinD_10		: "",	// コイン表示10の桁
		classCoinD_1		: "",	// コイン表示1の桁	-1:消灯　0～9:数字点灯
		classEnemyHand		: "",	// グー,チョキ,パー,消灯 のモニター表示
		classPlayerHandB	: "",	// BETボタン
		classPlayerHandR	: "",	// グーボタン
		classPlayerHandS	: "",	// チョキボタン
		classPlayerHandP	: "",	// パーボタン
		classResultLumpW	: "",	// Winランプ
		classResultLumpD	: "",	// Drawランプ
		classResultLumpL	: "",	// Loseランプ
		classRoulette		: ""	/* ルーレット板
			ID		タイプ	番号	
			0		Dark	default	
			1～5	Quad	1～5	
			6～25	Single	1～20	
		*/
	},
	methods:{
		init:function(){
			this.scene		  	= "open";
			this.battle_result	= "";
			this.e_hand		  	= "rock";
			this.p_hand		  	= "";
			this.message	  	= "";
			this.recepted	  	= "true";
			this.coin		  	= 0;
			this.roulette_cell	= 0;
			
			this.classCoinD_100		= "num_10";
			this.classCoinD_10		= "num_10";
			this.classCoinD_1		= "num_10";
			this.classEnemyHand		= "black";
			this.classPlayerHandB	= "off";
			this.classPlayerHandR	= "off";
			this.classPlayerHandS	= "off";
			this.classPlayerHandP	= "off";
			this.classResultLumpW	= "off";
			this.classResultLumpD	= "off";
			this.classResultLumpL	= "off";
			this.classRoulette		= "r00";
		},
		
		//UIボタン入力
		button_rock		:function()	{this.setMessage("rock");},
		button_scissors	:function()	{this.setMessage("scissors");},
		button_papar	:function()	{this.setMessage("papar");},
		button_bet		:function()	{this.setMessage("bet");},

		//UIボタン入力 共通処理
		setMessage		:function(mes){
			//mesで示すボタンを押した
			this.message	= mes;
			//ボタン操作に対して未処理状態である
			this.recepted	= false;
			//デバッグ中ならいつ何を押したか表示
			let debug = true;
			if(debug){
				let message_d = "clicked \""+ mes + "\" :" +new Date().toString();
				console.log(message_d);
			}
		},
		closeMessage:function(){
			this.recepted	= true;
		},
		/*==============================
		以下 VueBindClass 更新用
		*/
		updateCoin:function(num){
			//入力値をゼロパディング入り文字列に変換
			let pad = ( '000' + num ).slice( -3 );
			//各桁の値を配列にする 内訳:[1の桁,10の桁,100の桁]
			let nums = [...pad].reverse();
			//入力値を上回る桁のゼロは消灯フラグに置き換える
			switch(true){
				case num<10 : nums[1] = 10;
				case num<100: nums[2] = 10;
				default:
			};
			//数値のクラス名変換用ラムダ
			let numToClass = function(s){return "num_" + s;};
			//各桁にクラスを適用
			this.classCoinD_1   = numToClass(nums[0]);
			this.classCoinD_10  = numToClass(nums[1]);
			this.classCoinD_100 = numToClass(nums[2]);
		},
		updateEnemyHand:function(num){
			//クラス名を列挙
			let calsses = ["rock","scissors","papar","black"];
			//0～2以外の入力値を3に丸める
			let target	= [0,1,2].includes(num)?num:3;
			//クラス名配列から入力値で値を選択
			let result	= calsses[target];
			//クラス適用
			this.classEnemyHand = result;
			//デバッグelementに書込
			this.e_hand = result;
		},
		updateRoulette:function(num){
			//入力値をゼロパディングを入り文字列に変換
			let pad = ( '00' + num ).slice( -2 );
			//クラス名取得
			let result = "r" + pad;
			//クラス適用
			this.classRoulette = result;
		},
		updatePlayerHandB:function(flag){ this.classPlayerHandB = flag ? "on" : "off";},
		updatePlayerHandR:function(flag){ this.classPlayerHandR = flag ? "on" : "off";},
		updatePlayerHandS:function(flag){ this.classPlayerHandS = flag ? "on" : "off";},
		updatePlayerHandP:function(flag){ this.classPlayerHandP = flag ? "on" : "off";},
		updateResultLumpW:function(flag){ this.classResultLumpW = flag ? "on" : "off";},
		updateResultLumpD:function(flag){ this.classResultLumpD = flag ? "on" : "off";},
		updateResultLumpL:function(flag){ this.classResultLumpL = flag ? "on" : "off";},
		//ショートハンド
		//RSP各ボタンへ一括で点灯状態指定
		updatePlayerHandRSP:function(flag){
			let methods = [this.updatePlayerHandR, this.updatePlayerHandS, this.updatePlayerHandP];
			methods.forEach(v=>v(flag));
		},
		//ルーレットをQuad表示で更新
		updateRouletteQuad:function(num){
			let num_ = 1 + (num % 5);
			this.updateRoulette(num_);
		},
		//ルーレットをSingle表示で更新
		updateRouletteSingle:function(num){
			let num_ = 6 + (num % 20);
			this.updateRoulette(num_);
		},
		//ルーレットを消灯させる
		updateRouletteOff:function(){this.updateRoulette(0);},
	}
});
