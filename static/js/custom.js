
$(".radioCheck").change(function(){
	var result = $(this).val();
	$(".btn_cate_edit").attr("data-href",result);
});

$(".btn_cate_edit").click(function(){
	var ref = $(this).attr("data-href");
	var url = window.location.hostname + "/download/category/" + ref;
	
	window.location.replace("//"+url);
});