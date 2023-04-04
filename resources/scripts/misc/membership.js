(function ($) {

    $(document).ready(function(){
        let myArray = [];
        $(document).on('click','#add-feature',function(e){
            e.preventDefault();
            var selectedValues = $('#plan_feature').val();
            if (selectedValues !== null) {
                // $('#input_fields').empty();
                $.each(selectedValues, function(index, value) {
                    let optionText = $('#plan_feature option[value="' + value + '"]').text(); // Get the text value of the selected option
                    console.log(optionText);
                    let obj = {
                        "id": value,
                        "name": optionText,
                        "value": "",
                        "resetable_value": "",
                        "resettable_month": "",

                    };
                    if (checkIdExists(obj.id)) {
                        return false;
                    } else {
                        console.log('Object with id ' + obj.id + ' does not exist in myArray');
                        myArray.push(obj);
                    }
                });

            }
            $('#input_fields').empty();
            render();
        });
        function checkIdExists(id) {
            console.log(myArray.some(obj => obj.id === id));
            return myArray.some(obj => obj.id === id);
        }
        function render(){
            let html = '';
                for (let i = 0; i < myArray.length; i++) {
                    const obj = myArray[i];
                    const featureName = 'feature[' + i + ']';
                    html += '<div class="' + obj.id + '">' +
                        '<input type="hidden" name="' + featureName + '[id]" value="' + obj.id + '">' +
                        '<input type="text" name="' + featureName + '[value]" placeholder=" Enter '+ obj.name+' value">' +
                        '<input type="text" name="' + featureName + '[resettable_period]" placeholder=" Enter '+ obj.name+' Resetable Period">' +
                        '<input type="text" name="' + featureName + '[resettable_interval]" placeholder=" Enter '+ obj.name+' Resetable Interval" style="float: none !important;">' +
                        '</div><br>';

                }
            // if (html !== '') {
                $('#input_fields').append(html);
            // }

        }

        console.log(myArray);

    });

})(jQuery);
