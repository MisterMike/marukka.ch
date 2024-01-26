import request from "superagent";

var agent = request.agent();
function plugin(request) {
    request.on("error", err => {
        if (err.response && err.response.status && err.response.status === 500) {
            alert("Onvoorziene fout. Contacteer technische support.");
        }
    });
}
agent.use(plugin);

export default agent;
