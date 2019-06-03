jQuery(document).ready(function() {
    $("#select_contractor_form_name").change(function(){

        $("#selectContractorButton").prop('disabled', true);

        var contractorType = $("#select_contractor_form_contractor").attr("contractorType");
        var url = "";

        switch(contractorType) {
            case "customer": url = "/api/get/customers/by/country/";break;
            case "supplier": url = "/api/get/suppliers/by/country/";break;
        }

        $.ajax({url: url+$(this).val(), dataType: 'json', success: function(result){
                $("#select_contractor_form_contractor").children('option:not(:first)').remove();

                $.each(result.contractors,function(key, contractor)
                {
                    //alert(contractor.id)
                    $("#select_contractor_form_contractor").append('<option value=' + contractor.id + '>' + contractor.name + '</option>');
                });

                $("#select_contractor_form_contractor").prop('disabled', false);
        }});
    });


    $("#select_contractor_form_contractor").change(function(){
        if ($('#select_contractor_form_contractor').find(":selected").val() !== '') {
            $("#selectContractorButton").prop('disabled', false);
        }
        else {
            $("#selectContractorButton").prop('disabled', true);
        }
    })

    $('#selectContractorButton').click(function(){
        $('#selectContractor').attr('action', '/user/show/contractor/'+$("#select_contractor_form_contractor").val());
    });
});