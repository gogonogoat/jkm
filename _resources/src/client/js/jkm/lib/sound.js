/*==============================
Desc:
	効果音に関する処理を何度も書きたくない
	
	リピート再生とレジューム再生に関して追加。
*/
class Sound extends Audio{
	constructor(url,repeat=false){
		super(url);
		this.setRepeat(repeat);
	}
	play(restart=true){
		if(restart) this.currentTime = 0;
		super.play();
	}
	setRepeat(flag){if(flag) this.onended = this.play;}
}