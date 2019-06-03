$(document).ready(function(){
    $(".delete").click(function(){
        if (!confirm("Вы действительно хотите выполнить удаление?")){
            return false;
        }
    });
});