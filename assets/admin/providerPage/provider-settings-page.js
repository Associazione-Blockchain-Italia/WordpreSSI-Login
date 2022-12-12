const providerSettingsTestResultsDivID = "#provider-configuration-test-results-div";

function displayErrors(errors){
    const providerSettingsTestResultsDivDOMElement = jQuery(providerSettingsTestResultsDivID);
    const errorsHtml = Object.keys(errors)
        .map(e=>`<p><span>${errors[e]}</span></p>`)
        .join();
    providerSettingsTestResultsDivDOMElement.empty();
    providerSettingsTestResultsDivDOMElement.append(errorsHtml);
    providerSettingsTestResultsDivDOMElement.addClass("notice notice-error");
    providerSettingsTestResultsDivDOMElement.removeClass("notice-success");
    providerSettingsTestResultsDivDOMElement.css('display','block');
}

function displaySuccess(){
    const providerSettingsTestResultsDivDOMElement = jQuery(providerSettingsTestResultsDivID);
    providerSettingsTestResultsDivDOMElement.empty();
    providerSettingsTestResultsDivDOMElement.append("<p><span>Configuration Ok!</span></p>");
    providerSettingsTestResultsDivDOMElement.addClass("notice notice-success");
    providerSettingsTestResultsDivDOMElement.removeClass("notice-error");
    providerSettingsTestResultsDivDOMElement.css('display','block');
}

function displayResults(results){
    if(results && Object.keys(results).length > 0){
        displayErrors(results);
    }
    else{
        displaySuccess();
    }
}

function providerConfigurationTest(provider){
    console.log("-> Test Provider ", provider);
    let postFields = {
        'provider': provider,
        'action': 'providerConfigurationTest',
    }
    jQuery.post(
        ajaxUrl,
        postFields,
        (response) => {
            let parsedResponse = JSON.parse(response);
            console.log("--> ", parsedResponse.data);
            displayResults(parsedResponse.data.args);
        }
    );
}


