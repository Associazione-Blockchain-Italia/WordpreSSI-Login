let interval;
const providers = ajax_object.providers?.reduce((r,p) => ({...r, [p.id]: p}), {});
const homeUrl = ajax_object.home_url;
const ajaxUrl = ajax_object.ajax_url;

const providerErrorNoNextAction = "Provider Error: invalid 'nextAction' value.";

let ssiFlow = {
    externalPage(args) {
        window.location.replace(args.redirectUrl);
    },
    verifyCredential: (params) => {
        console.log("-> Verify Credential ", params);
        let postFields = {
            'provider': params.provider,
            'action': 'verifyCredential',
            'args': params.args
        }
        jQuery.post(
            ajaxUrl,
            postFields,
            (response) => {
                let parsedResponse = JSON.parse(response);
                console.log("--> ", parsedResponse.data);
                if (parsedResponse.status >= 400) {
                    displayErrors(parsedResponse.errors);
                } else {
                    let responseData = parsedResponse?.data;
                    let nextAction = responseData?.nextAction;
                    if (!ssiFlow[nextAction]) {
                        displayErrors(providerErrorNoNextAction);
                    } else {
                        ssiFlow[nextAction](responseData);
                    }
                }
            }
        )
    },
    pollingVerify(params) {
        pollingVerifyAnimation(params);
        interval = setInterval(() => {
            console.log("-> Polling Verify ", params);
            let postFields = {
                'action': 'getVerification',
                'provider': params.provider,
                'verificationId': params.verificationId,
                'args': params.args,
            };
            jQuery.post(
                ajaxUrl,
                postFields,
                (response) => {
                    let parsedResponse = JSON.parse(response);
                    postFields.args = parsedResponse?.data?.args;
                    clearInterval(interval);
                    if (parsedResponse.status >= 400) {
                        displayErrors(parsedResponse.errors);
                    } else {
                        let state = parsedResponse?.data?.state;
                        let isValid = parsedResponse?.data?.isValid;
                        if (state === "Accepted" && isValid === true) {
                            jQuery("#qrcode").hide();
                            window.location.replace(homeUrl);
                        }
                        else{
                            this.pollingVerify({...params, ...postFields});
                        }
                    }
                })
        }, 3000)
    },
    createAndOfferCredential: (params) => {
        let identifier = Math.floor(Math.random() * 16777215).toString(16)
        let role = 'subscriber';
        let postFields = {
            'action': 'createCredential',
            'provider': params.provider,
            'identifier': identifier,
            'role': role,
        }
        jQuery.post(
            ajaxUrl,
            postFields,
            (response) => {
                let parsedResponse = JSON.parse(response);
                console.log('Create Credential -->', parsedResponse.data)
                if (parsedResponse.status >= 400) {
                    displayErrors(parsedResponse.errors);
                } else {
                    let responseData = parsedResponse?.data;
                    let nextAction = responseData?.nextAction;
                    if (!ssiFlow[nextAction]) {
                        displayErrors(providerErrorNoNextAction);
                    } else {
                        responseData.identifier = identifier
                        responseData.role = role
                        ssiFlow[nextAction](responseData);
                    }
                }
            });
    },
    pollingIssue: (params) => {
        pollingIssueAnimation(params);
        interval = setInterval(() => {
            let postFields = {
                'action': 'getCredential',
                'provider': params.provider,
                'identifier': params.identifier,
                'role': params.role,
                'credentialId': params.credentialId,
                'args': params.args,
            };
            jQuery.post(
                ajaxUrl,
                postFields,
                (response) => {
                    let parsedResponse = JSON.parse(response);
                    postFields.args = parsedResponse?.data?.args;
                    console.log('Polling Issue -->', parsedResponse.data)
                    if (parsedResponse.status >= 400) {
                        displayErrors(parsedResponse.errors);
                        clearInterval(interval);
                    } else {
                        let state = parsedResponse?.data?.state;
                        if (state === "Issued") {
                            jQuery("#qrcode").hide();
                            clearInterval(interval);
                            ssiFlow.verifyCredential(params);
                        }
                    }
                });
        }, 3000);
    }
}


