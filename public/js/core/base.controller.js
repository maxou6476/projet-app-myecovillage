export class BaseController {

	/**
	 * @type {object}
	 */
	params = {};
	id = "";
	ressourcePath = "";

	constructor(params) {
		this.params = params;
		this.view = `${baseUrl}/${ressourcePath}`;
		this.log("Controller loaded");
	}

	/**
	 * @return {Promise<string>} html content
	 */
	async loadView() {
		if (!this.view)
			this.error("No view found!");
		else {
			this.log("Loading view...");
			let response = await fetch(this.view);
			let html = await response.text();
			this.log("View loaded");
			return html;
		}
	}

	/**
	 * @param {string} message
	 */
	log(message) {
		console.log(`[${this.constructor.name}] ${message}`);
	}

	/**
	 * @param {string} message
	 */
	error(message) {
		console.error(`[${this.constructor.name}] ${message}`);
	}


}