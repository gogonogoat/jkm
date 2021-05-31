/*==============================
Desc:
	アセットをプリロードすべき状況に遭遇してしまった
Note:
	URL文字列の配列を準備する工程に関して
		ディレクトリ構成をJSON調に表現したオブジェクトを書いて
		TreeParserクラスで展開した配列を用いる。
	
	「アセットをプリロードすべき状況」って具体的に何
		ルーレットのような高速でテクスチャを張り替える動作を
		初回起動時に行った際にローディングが間に合わず
		演出が盛大に崩れる状況
		
	Warning出てるんだけど何これ
		結論から言えば特に気にする必要はない
		
		以下詳細
			「プリロードした物がonload処理終了後から
			　一定期間内に使われなかったけど
			　ひょっとして処理を見直した方がいいんじゃないかな？」
			的なメッセージ
			
			プリロードした物であっても
			その時々で使われたり使われなかったりする
			故に大して気にする必要はない
			
			精々気にするとしたらプリロードの際のロード順の調整だ
			ロード順はオブジェクトに対するfor in等の出力順に限定される
			これはプリロード対象の中での優先順と必ずしも一致はしない

			故に優先度の高いアセットに限定したオブジェクトを書いて
			プリロードDOMを先に吐かせるのも手段のうちだろう
			
			ただし今はそこにこだわるべき状況ではないので割愛する
*/
class Preloader{
	/*
	Desc: 
	Copy:Preloader.loadAll(assets);
		
	*/
	//配列内にある全URL文字列のアセットをプリロードする
	static loadAll(assets){assets.forEach(Preloader.load)};

	//指定したURL文字列のアセットをプリロードする
	static load(url){
		let elm = document.createElement('link');
		elm.rel = 'preload';
		elm.as = Preloader.typeHint(url);
		elm.href = url;
		document.head.appendChild(elm);
	}
	//URL文字列からlinkタグのasプロパティに指定する値を取得する
	static typeHint(url){
		let ext = Preloader.extractExtension(url);
		let includes = Object.keys(Preloader.types).includes(ext);
		let result = includes ? Preloader.types[ext] : null;
		return result;
	}
		
	//URL文字列の末尾から拡張子を抽出する
	static extractExtension(url){
		//最後の.以降の文字列を小文字で取得
		let literal = /.*\.(.*)$/;
		let order = "$1";
		let result = url.replace(literal,order).toLowerCase();
		return result;
	}
	//typeHintメソッドで使う定数
	static get types(){
		let img = "image";
		let audio = "audio";
		let types = {
			png		: img,
			gif		: img,
			bmp		: img,
			jpg		: img,
			jpeg	: img,
			ogg		: audio,
			mp3		: audio,
			wav		: audio,
		};
		return types;
	}
}