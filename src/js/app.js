function removeOptionExlusiva(selectElement) {
    //optener el valor que se selecciona
    var selectedValue = selectElement.value;

    // Obtén todos los valores del select
    var allSelects =document.querySelectorAll('select.form-control.exclusiva');

    
    for(var i = 0; i < allSelects.length; i++) {
        var select = allSelects[i];
          // Obtener el nombre de la prueba asociada al select

        if(select !== selectElement) {
            for(var j = 0; j < select.options.length; j++) {
                var option = select.options[j];

                
                if(option.value === selectedValue) {
                    // Borra la opcion
                    select.remove(j);
                }
            }
        }
    }
}
function removeOptionRelevos(selectElement) {
    //optener el valor que se selecciona
    var selectedValue = selectElement.value;

    // Obtén todos los valores del select
    var allSelects =document.querySelectorAll('select.form-control.relevos');

    
    for(var i = 0; i < allSelects.length; i++) {
        var select = allSelects[i];
          // Obtener el nombre de la prueba asociada al select

        if(select !== selectElement) {
            for(var j = 0; j < select.options.length; j++) {
                var option = select.options[j];

                
                if(option.value === selectedValue) {
                    // Borra la opcion
                    select.remove(j);
                }
            }
        }
    }
}