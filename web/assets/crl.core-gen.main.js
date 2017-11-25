$('#table_name').on('change', function(){
   var obj = $(this);
   var infoBlock = $('.info-block');
   if (obj.val().trim().length < 1){
       infoBlock.find('input').prop('disabled', true);
       $('[data-field=table_name]').val('');
       $('[data-field=model_name]').val('');
       return;
   }
   var tableName = obj.val().trim();
    $('[data-field=table_name]').val(tableName).blur();
    var modelClass = '';
    $.each(tableName.split('_'), function() {
        if(this.length>0)
            modelClass+=this.substring(0,1).toUpperCase()+this.substring(1);
    });
    $('[data-field=model_name]').val(modelClass).blur();
    infoBlock.find('input').prop('disabled', false);
});

$('[data-field=model_name]').on('blur', function(){
    if ($(this).val().trim().length > 0){
        $('input[type=submit]').prop('disabled', false);
    } else {
        $('input[type=submit]').prop('disabled', true);
    }
});
