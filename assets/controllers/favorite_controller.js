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
    // static targets = ["name"];
    add(event) {
        // console.log(event);
        const link = event.target.dataset.link;
        this.load(link, event.target);
    };

    load(url, element) {
        fetch(url)
          .then(response => response.json())
          .then(data => {
            if (data[0] == "success") {
              if (data[1] === "add") {
                if (element.classList.contains("add")) {
                  element.classList.replace("add", "remove");
                  element.innerText = "retirer des favoris";
  
                }  
              }
              else if (data[1] === "del") {
                if (element.classList.contains("remove")) {
                  element.classList.replace("remove", "add");
                  element.innerText = "ajouter aux favoris";
                }
              }
            }
          })
      }
}
