
$(".radioCheck").change(function(){
	$(".radioCheck").parent().removeAttr("style");
	var result = $(this).val();
	$(this).parent().css("color","red");
	$(".btn_cate_edit").attr("data-href",result);
});

$(".btn_cate_edit").click(function(){
	var ref = $(this).attr("data-href");
	var url = window.location.hostname + "/download/category/" + ref;
	
	window.location.replace("//"+url);
});