/*==============================
Desc:
	if(bool)func();な構文を並べるのに嫌気が差した
*/
class TaskBullet{
	constructor(isAllow,func){
		this.isAllow = Boolean(isAllow);
		this.func = func;
		let uncallable = func.constructor.name != "Function";
		if(uncallable)throw new Error("callableではない値が入力されました");
	}
	fire(){if(this.isAllow)this.func();}
}