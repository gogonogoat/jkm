class ModeRoulette extends Mode{
	constructor(parent){super(parent);}
	//ルーレットが1セル進むのに必要なフレーム数
	get rouletteSpeed(){return 1 / Math.PI * 2;}
	//ルーレット板における各セルの値
	get rootTable(){return [50,2,5,7,3,5,1,2,7,5,3,1,5,7,3,1,5,2,7,5];}
}