class ModeResult extends Mode{
constructor(parent){super(parent);}
	//決着の種類
	get results(){return ["win","draw","lose"];}
	
	//指定された手を配列のインデックス値に変換
	handToIndex(parent,hand){
		let result  = parent.cards.indexOf(hand);
		return result;
	}
	//指定した手に勝てる手を取得
	winHand(parent,hand) {
		//指定配列を複製、外した尻を頭の上に取り付けて返す関数
		let unshiftRoll = function(arr){
			let result = [...arr];
			let hip = result.pop();
			result.unshift(hip);
			return result;
		};
		//上記関数を用いて勝てる手の配列を生成
		let cardsAdv = unshiftRoll(parent.cards);
		//指定された手をインデックスに変換
		let index = this.handToIndex(parent, hand);
		//配列とインデックスから値を取得
		let result = cardsAdv[index];
		return result;
	}
	//指定した手に負ける手を取得
	loseHand(parent,hand){
		//指定配列を複製、外した頭を尻の後ろに取り付けて返す関数
		let shiftRoll = function(arr){
			let result = [...arr];
			let head = result.shift();
			result.push(head);
			return result;
		};
		//上記関数を用いて負ける手の配列を生成
		let cardsDis = shiftRoll(parent.cards);
		//指定された手をインデックスに変換
		let index = this.handToIndex(parent, hand);
		//配列とインデックスから値を取得
		let result = cardsDis[index];
		return result;
	}
	/*==============================
	Desc:周期毎に出した手を点滅させる
	Note:
		継承先でthis.holdIndexへInt値として手を入れてから
		cycle毎にこのメソッドを実行させて使う
		
		全ての継承先で同じ文を書いていたので
		親クラスに吸収させることにした。
	*/
	handBlink(cycle, timer){
		let counter = CountTrigger.FireTimes(cycle, timer);
		let hand = (counter % 2) ? 3 : this.holdIndex;
		this.parent.game.updateEnemyHand(hand);
	}
}