$().ready(function(){$(".card").on("click",function(){var a=$.parseJSON($(this).attr("data-fields"));$.each(a,function(a,c){a=a.replace("[","\\[").replace("]","\\]"),"checkbox"==$("[id = "+a+"]").attr("type")?$("[id = "+a+"]").prop("checked","1"==c):$("[id = "+a+"]").val(c)}),$(".card-container").show()}),$(".btn-add-card").on("click",function(){$("#card").trigger("reset"),$("#card #id").val("-1"),$(".card-container").show()}),$(".modal-container-close").on("click",function(){$(".modal-container").hide()}),$(".btn-save").on("click",function(){$.ajax({type:"POST",dataType:"json",url:"php/ajax.php",data:{call:"save_card",form:$("#card").serialize()},success:function(a){if(a.success){console.log(a.output.card);var c=$.parseJSON(a.output),t=$(".card[data-id="+c.id+"]");t.attr("data-fields",a.output),t.find(".card-image").css("background-image","url("+c.img+")"),t.find(".card-title").text(c.name),$(".modal-container").hide()}else alert(a.output)}})})}),$().ready(function(){$(".login-modal-form input").on("keypress",function(a){13==a.which&&$(this).siblings(".btn").click()}),$(".btn-login").on("click",function(){$.ajax({type:"POST",dataType:"json",url:"php/ajax.php",data:{call:"login",form:$("#login").serialize()},success:function(a){a.success?location.reload():alert(a.output)}})}),$(".btn-logout").on("click",function(){$.ajax({type:"POST",dataType:"json",url:"php/ajax.php",data:{call:"logout"},success:function(a){a.success?location.reload():(alert("Something went wrong!"),location.reload())}})})});