/*==============================
Desc:
	何フレーム毎に実行とか何回周期が回ったとか書くのが面倒になった
*/
class CountTrigger{
	/*
	Desc: 
		発火周期とカウンターを指定して
		発火するタイミングであるかを取得
	Param:
		cycle	Int		周期
		count	Int		カウンタ
	Return:
		Bool	True 	発火するタイミングである
				Flase	そうではない
	Copy:
		CountTrigger.isFireTime(cycle,count)
	*/
	static isFireTime(cycle,count){return (count % cycle==0);}
	/*
	Desc: 
		発火周期とカウンターを指定して
		何回の周期を終えているかを取得
	Param:
		cycle	Int		周期
		count	Int		カウンタ
	Return:
		Int		周期を終えた回数
	Copy:
		CountTrigger.FireTimes(cycle,count)
	*/
	static FireTimes(cycle,count){return Math.floor(count/cycle)}
}