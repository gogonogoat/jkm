/*==============================
Desc:
	ajaxの際にHTTPステータスコードに関する
	テンプレを書くのが面倒になった
*/
class HttpStatus{
	static isSuccess(state){return (state>=200 && state<400);}
	static isFailed(state) {return !(HttpStatus.isSuccess(state));}
}