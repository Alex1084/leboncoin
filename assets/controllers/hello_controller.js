import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    static targets = ["output"];
    addImage() {
        let count = this.data.get("count");
        const divInput = document.getElementById("article_form_images").dataset.prototype;
        let input = divInput.replace(/__name__/g , count);
        this.outputTarget.innerHTML += input;
        count++;
        this.data.set("count", count++);
    };

    /* count() {
        return this.data.set("count");
    }
    count(value) {
        return this.data.set("count") = value;
    } */
}
