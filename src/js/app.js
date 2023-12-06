function ocultarValoresSeleccionadosExclusivas() {
    var allSelects = document.querySelectorAll('select.form-select.exclusiva');
  
    allSelects.forEach(function(selectElement) {
      var selectedValue = selectElement.value;
  
      allSelects.forEach(function(currentSelect) {
        Array.from(currentSelect.options).forEach(function(option) {
          if (option.value === selectedValue && selectedValue !== "0") {
            option.style.display = "none";
          }
        });
      });
    });
  }
  document.addEventListener("DOMContentLoaded", function() {
    ocultarValoresSeleccionadosExclusivas();
    ocultarValoresSeleccionadosRelevos();
  });
function removeOptionExlusiva(selectElement) {
    var selectedValue = selectElement.value;
    var allSelects = document.querySelectorAll('select.form-select.exclusiva');

    for (var i = 0; i < allSelects.length; i++) {
        var select = allSelects[i];

        if (select !== selectElement) {
            for (var j = 0; j < select.options.length; j++) {
                var option = select.options[j];

                if (option.value === selectedValue && selectedValue !== "0") {
                    option.style.display = "none"; // Oculta la opción seleccionada
                }
               
            }
        }
    }
   
        resetOptionsExclusivas();
    
    
} 
function resetOptionsExclusivas() {
    var allOptions = document.querySelectorAll('select option');
    var selectedValues = Array.from(document.querySelectorAll('select.form-select.exclusiva')).map(select => select.value);
  
    allOptions.forEach(option => {
      if (!selectedValues.includes(option.value)) {
        option.style.display = "block";
      }
    });
  }
function removeOptionRelevos(selectElement) {
    var selectedValue = selectElement.value;
    var allSelects = document.querySelectorAll('select.form-select.relevos');

    for (var i = 0; i < allSelects.length; i++) {
        var select = allSelects[i];

        if (select !== selectElement) {
            for (var j = 0; j < select.options.length; j++) {
                var option = select.options[j];

                if (option.value === selectedValue && selectedValue !== "0") {
                    option.style.display = "none"; // Oculta la opción seleccionada
                }
               
            }
        }
    }
   
        resetOptionsRelevos();
    
    
} 
function resetOptionsRelevos() {
    var allOptions = document.querySelectorAll('select option');
    var selectedValues = Array.from(document.querySelectorAll('select.form-select.relevos')).map(select => select.value);
  
    allOptions.forEach(option => {
      if (!selectedValues.includes(option.value)) {
        option.style.display = "block";
      }
    });

  }
function ocultarValoresSeleccionadosRelevos() {
var allSelects = document.querySelectorAll('select.form-select.relevos');

allSelects.forEach(function(selectElement) {
    var selectedValue = selectElement.value;

    allSelects.forEach(function(currentSelect) {
    Array.from(currentSelect.options).forEach(function(option) {
        if (option.value === selectedValue && selectedValue !== "0") {
        option.style.display = "none";
        }
    });
    });
});
}