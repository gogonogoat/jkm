/*==============================
Desc:
	アセットのプリロードを行う事に関して
	全URLを愚直に並べる事態をどうにも受け入れがたい
*/
class TreeParser{
	constructor(root,tree){
		this.root = root;
		this.tree = tree;
		this.parse();
	}
	
	parse(){
		this.parsed = [];
		this.roughSolve(this,"",this.tree);
		this.readProof();
	}
	
	//オブジェクトのツリーを再帰的に読んで文字列の配列を作成
	roughSolve(self,parent,value){
		let valuesClass = value.constructor.name;
		let merge = ( (x,y) => (x+"/"+y) );
		let objDigger = function(v){self.roughSolve(self,merge(parent,v[0]),v[1])};
		let push = function(v){self.parsed.push(merge(parent,v));}
		switch(valuesClass){
			case "String"	: push(value);
			break;
			case "Object"	: Object.entries(value).forEach(objDigger);
			break;
			case "Array"	: value.forEach(push);
			break;
		};
	}
	
	//roughSolveの際に生じたノイズ除去＆ルート指定の適用
	readProof(){
		let fix = ( v => this.root + v.slice(1) );
		this.parsed = this.parsed.map(fix);
	};
}