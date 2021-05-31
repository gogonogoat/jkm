/*==============================
Desc:
	if(bool)func();な構文を並べるのに嫌気が差した
	TaskBulletで用意した弾を
	このクラスによって一度の引き金で全弾発射する
*/
class TaskTrigger{
	static fire(bullets){
		let magazine = TaskTrigger.magazine(bullets);
		let fire = ( v=>v.fire() );
		magazine.forEach(fire);
	}
	/*==============================
	Desc:入力に対応したTaskBulletの配列を吐く
	Note:
	*/
	static magazine(bullets){
		let isSingleBullet = TaskTrigger.isClassName(bullets,"TaskBullet");
		let result = isSingleBullet ?
			[bullets]:
			TaskTrigger.charge(bullets);
		return result;
	}

	/*==============================
	Desc:入力配列に対応したTaskBulletの配列を吐く
	Note:
	*/
	static charge(trash){
		//よくわからない入力を弾として揃える
		let bullets = trash.map(TaskTrigger.product);
		//Nullを除外するフィルタ
		let rejectNull = (v=>(v!=null));
		//Nullを含まない弾の集まりを取得
		let result = bullets.filter(rejectNull);
		return result;
	}
	/*==============================
	Desc:入力に対応したTaskBulletを吐く
	Note:
		配列を入力：弾を新規作成
		弾を入力：その弾をそのまま流す
		その他：ノイズの入力として警告しながらNullを吐く
	*/
	static product(bullet){
		let isMaybeMaterial = TaskTrigger.isClassName(bullet,"Array");
		let isSingleBullet  = TaskTrigger.isClassName(bullet,"TaskBullet");
		switch(true){
			case isMaybeMaterial:return new TaskBullet(bullet[0],bullet[1]);
			case isSingleBullet :return bullet;
			default :
			console.log("なんかここnull nullしてる");
			return null;
		}
	}
	//このクラスが持っている必要はないけどラムダ的な感じに置いておく
	static isClassName(target,className){
		let className_ = target.constructor.name;
		let result = ( className_ == className );
		return result;
	}
}
