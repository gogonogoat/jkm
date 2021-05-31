/*==============================
Desc:
	ランダムに関するショートハンドが欲しかった
*/
class Random{
	/*
	Desc: 0から指定数までの整数値を得る
	Copy:
		Random.intZeroToMax(max)
	*/
	static intZeroToMax(max){return Random.intZeroToUnderMax(max+1);}
	/*
	Desc: 0から指定数未満の整数値を得る
	Copy:
		Random.intZeroToUnderMax(max)
	*/
	static intZeroToUnderMax(max){return Math.floor(Math.random()*(max));}
}