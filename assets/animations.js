function showProviders() {
    jQuery('form#loginform').toggle();
    let providerList = jQuery('#ssishowproviders');
    let text = providerList?.text()?.trim();
    providerList.text(_buildProviderListTitle(text));
    if (text === 'Click here for the SSI Authentication') {
        Object.keys(providers).forEach((provider) => {
            jQuery(_buildProviderButton(provider)).insertAfter('#ssishowproviders');
        });
    } else {
        removeAllElements();
        clearInterval(interval);
    }
}

function pollingVerifyAnimation(args) {
    removeAllElements();
    jQuery(`<h1 id="ssipluginmessage">Login</h1>`).insertAfter(`#ssishowproviders`);
    jQuery("#qrcode-container").show();
    new QRious({element: jQuery("#qrcode")[0], value: args?.verificationUrl, size:280});
    jQuery(`.loading-ring`).css('display', 'inline-block');
}

function pollingIssueAnimation(args) {
    removeAllElements();
    jQuery("#qrcode-container").show();
    new QRious({element: jQuery("#qrcode")[0], value: args?.offerUrl, size:280});
    jQuery(`.loading-ring`).css('display', 'inline-block');
    jQuery(`<h1 id="ssipluginmessage">Sign Up</h1>`).insertAfter('#ssishowproviders');
}

function displayErrors(errors, defaultMessage = "An Error has occurred!") {
    let message = Array.isArray(errors) ? errors.join(" \n") : errors;
    alert(message ? message : defaultMessage);
}

function removeAllElements() {
    jQuery("#qrcode").remove();
    jQuery("#qrcode-container").hide().append("<canvas id='qrcode'></canvas>")
    Object.keys(providers).forEach((providerId) => {
        jQuery(`.loading-ring`).hide();
        jQuery(`#ssipluginmessage`).remove();
        jQuery(`#${providerId}Button`).remove();
        jQuery(`#${providerId}SignupButton`).remove();
        jQuery(`#${providerId}LoginButton`).remove();
    })
}

function _buildProviderButton(providerId) {
    return `
        <button 
            id="${providerId}Button"
            class="ssiButton providerButton"
            onclick="showLoginSignup('${providerId}')"
        >
        ${providers[providerId].name}
        </button>
      `;
}

function _buildProviderSignupButton(providerId) {
    return `
        <button 
            id="${providerId}SignupButton" 
            class="ssiButton signupButton" 
            onclick="ssiFlow.createAndOfferCredential({provider:'${providerId}'})">
            Sign Up
        </button>
   `;
}

function _buildProviderLoginButton(providerId) {
    return `
        <button 
            id="${providerId}LoginButton" 
            class="ssiButton loginButton" 
            onclick="ssiFlow.verifyCredential({provider:'${providerId}'})">
            Login
        </button>
   `;
}

function showLoginSignup(providerId) {
    removeAllElements();
    jQuery(_buildProviderSignupButton(providerId)).insertAfter('#ssishowproviders')
    jQuery(_buildProviderLoginButton(providerId)).insertAfter('#ssishowproviders')
}

function _buildProviderListTitle(currentText) {
    if (currentText?.trim() === 'Click here for the SSI Authentication')
        return '← Back to the classic login';
    return 'Click here for the SSI Authentication';
}
