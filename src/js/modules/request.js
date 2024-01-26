import request from "./superagent";

const dataRequest = function(action, token, obj) {
    var dto = { action: action };

    for (var attrname in obj) {
        dto[attrname] = obj[attrname];
    }

    var req = request
        .post(window.location.href)
        .type("form")
        .send(dto)
        .set({
            "X-CSRF-Token": token
        })
        .set("Accept", "application/json, text/javascript");

    return req;
};

const getSaveSurvey = (token, params) => {
    return dataRequest("keplerstein-module/sharpspring/save-survey", token, params);
};

export {
    dataRequest,
    getSaveSurvey,
};
