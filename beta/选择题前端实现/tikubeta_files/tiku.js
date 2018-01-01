var timeState = true;//时间状态 默认为true 开启时间
var HH = 0;//时
var mm = 0;//分
var ss = 0;//秒
/*实现计时器*/
var time = setInterval(function () {
	if (timeState) {
		if (HH == 24) HH = 0;
		str = "";
		if (++ss == 60) {
			if (++mm == 60) { HH++; mm = 0; }
			ss = 0;
		}
		str += HH < 10 ? "0" + HH : HH;
		str += ":";
		str += mm < 10 ? "0" + mm : mm;
		str += ":";
		str += ss < 10 ? "0" + ss : ss;
		$(".time").text(str);
	} else {
		$(".time").text(str);
	}
}, 1000);
/*开启或者停止时间*/
$(".time-stop").click(function () {
	timeState = false;
	$(this).hide();
	$(".time-start").show();
});
$(".time-start").click(function () {
	timeState = true;
	$(this).hide();
	$(".time-stop").show();
});
/*答题卡*/
function showcard() {
	$("#closeCard").show();
	$("#answerCard").slideDown();
	$("#openCard").hide();
}
function closecard() {
	$("#openCard").show();
	$("#answerCard").slideUp();
	$("#closeCard").hide();
}


var qs = [];
var ops = ["A", "B", "C", "D"];
for (var i = 0; i < 10; i++) {
	opts = {};
	for (o in ops) {
		opts[o] = "q " + i + "op " + o;
	}
	qs.push({ qid: i, question: "question " + i, options: opts, answer: "answer of " + i, type: "" });
}

var blank_qs = [];
for (var i = 0; i < 10; i++) {
	blank_qs.push({ qid: i, question: "blank question " + i, answer: "answer of " + i, type: "blank" });
}

var cal_qs = [];
for (var i = 0; i < 10; i++) {
	cal_qs.push({ qid: i, question: "cal question " + i, answer: "answer of " + i, type: "cal" });
}
//根据参数qs生成列表，每个列表项可点击切换
function generateList(qs) {
	var r = "";
	var maxLength = 30;
	for (var i = 0; i < qs.length; i++) {
		var a = "<li><a herf=\"#\" onclick=\"alterQuestionbyNum(" + i + ")\">";
		r += a + qs[i].question.substring(0, maxLength) + "</a></li>";
	}
	return r;
}

function getQ(qid, type) {
	if (type == "choice") {
		if (qid < qs.length && qid >= 0)
			return qs[qid];
		else return null;
	} else if (type == "blank") {
		if (qid < blank_qs.length && qid >= 0)
			return blank_qs[qid];
		else return null;
	}
	else if (type == "cal") {
		if (qid < cal_qs.length && qid >= 0)
			return cal_qs[qid];
		else return null;
	}
	return null;
}

function getQuestion() {
	var getQuestionUrl = "";
	var result;
	$.ajax(
		{
			url: getQuestionUrl,
			type: "get",
			data: {},
			success: function (re) {
				result = re;
			}
		}
	);
	return result;
}
// 需要渲染的公式使用两对大括号包裹
function render(text) {
	return text.replace(/\{\{\w*\}\}/g, function (word) {
		return katex.renderToString(word);
	})
}
var current_index = -1;
function alterQuestion(question) {
	$("#question p").html(render(question.question), { displayMode: true }).attr("qid", question.qid);
	$("#options").empty();
	var a = "<li><input type = \"radio\" name = \"option\" value = \"";
	var b = "\">";
	var c = "</li>"
	for (op in question.options) {
		$("#options").append(a + op + b + render(question.options[op]) + c);
	}
}


function alterQuestionbyNum(num) {
	current_index = parseInt(num);
	alterQuestion(qs[current_index]);
}

function alterprevQuestion() {
	i = i - 1;
	alterQuestion(i);
}

function alternextQuestion() {
	current_index++;
	$("#alertbox").hide();
	$("#alertbox1").hide();
	if (current_index < qs.length)
		alterQuestionbyNum(current_index);
	else current_index--;
}

function checkAnswer() {
	$("#alertbox").show();
	var checkAnswerUrl = "";
	var ans = $("#options :radio:checked");
	if (ans.length == 0) {
		document.getElementById("alertbox").setAttribute("class", "alert alert-warning");
		$("#result").text("至少需要选择一项");
		console.log("must select one");
		return;
	}
	ans = ans[0].value;
	var response;
	response = new Object();
	response.right = 0;
	// console.log(ans);
	// $.ajax(
	//     {
	//         url: checkAnswerUrl,
	//         type: "get",
	//         data: { "qid": 0, "answer": ans },
	//         success: function (re) {
	//             response = re;
	//         }
	//     }
	// );
	if (response.right == 1) {
		document.getElementById("alertbox").setAttribute("class", "alert alert-danger");
		$("#result1-1").text("正确");
	}
	else {
		document.getElementById("alertbox").setAttribute("class", "alert alert-danger");
		$("#result1-1").text("错误");
	}

}

function showqslist(){
	$("#panel-list4").show();
}

function reAnswer() {
	$("#alertbox1").show();
	$("#result1-2").text(getAnswer());
}


function getAnswer() {
	var getAnswerUrl = "";
	var qid = $("#question p").attr("qid");
	var response;
	$.ajax(
		{
			url: getAnswerUrl,
			type: "get",
			data: { "qid": qid },
			success: function (re) { response = re; }
		}
	);
	return response;
}

function getBlankQuestion() {
	var blankQuestionUrl = "";
	var result;
	$.ajax(
		{
			url: blankQuestionUrl,
			type: "get",
			data: {},
			success: function (re) {
				result = re;
			}
		}
	);
	return result;
}

function alterBlankQuestion(quest) {
	$("#question p").text(quest.question).attr("qid", quest.qid);
}
function getBlankAnswer() {
	var getBlankAnsUrl = "";
	var qid = $("#question p").attr("qid");
	var response;
	$.ajax(
		{
			url: getBlankAnsUrl,
			type: "get",
			data: { "qid": qid },
			success: function (re) { response = re; }
		}
	);
	return response;
}
function getCalQuestion() {
	var calQuestionUrl = "";
	var result;
	$.ajax(
		{
			url: calQuestionUrl,
			type: "get",
			data: {},
			success: function (re) {
				result = re;
			}
		}
	);
	return result;
}

function alterCalQuestion(quest) {
	$("#question p").text(render(quest.question)).attr("qid", quest.qid);
}
function getCalAnswer() {
	var getCalAnsUrl = "";
	var qid = $("#question p").attr("qid");
	var response;
	$.ajax(
		{
			url: getCalAnsUrl,
			type: "get",
			data: { "qid": qid },
			success: function (re) { response = re; }
		}
	);
	return response;
}a
function starQuestion(uid) {
	var starUrl = "";
	var qid = $("#question p").attr("qid");
	var response;
	$.ajax(
		{
			url: starUrl,
			type: "get",
			data: { "qid": qid, star: 1, "uid": uid },
			success: function (re) { response = re; }
		}
	);
	return response;
}

function cancelStar(uid) {
	var starUrl = "";
	var qid = $("#question p").attr("qid");
	var response;
	$.ajax(
		{
			url: starUrl,
			type: "get",
			data: { "qid": qid, star: 0, "uid": uid },
			success: function (re) { response = re; }
		}
	);
	return response;
}

function getAllStar(uid, type) {
	var getAllStarUrl = "";
	var uid = "";
	var response;
	$.ajax(
		{
			url: getAllStarUrl,
			type: "get",
			data: { "uid": uid, "type": type },
			success: function (re) { response = re; }
		}
	);
	return response;
}
	//以下js，fork from cs.kenji-special.info


	//
