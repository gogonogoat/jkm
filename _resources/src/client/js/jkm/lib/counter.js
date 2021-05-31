/*==============================
Desc:
	数値丸出しでカウンター制御するのに嫌気が差した
*/
class Counter{
	constructor(count=0){this.count=count;}
	reset(){this.set(0);}
	next(){this.count++;}
	set(value){this.count=value;}
	add(value){this.count+=value;}
}