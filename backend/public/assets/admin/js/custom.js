const Buttons = function() {
    //
    // Setup module components
    //

    // Progress buttons
    const _componentLadda = function() {
        if (typeof Ladda == 'undefined') {
            console.warn('Warning - ladda.min.js is not loaded.');
            return;
        }

        // Button with spinner
        Ladda.bind('.btn-ladda-spinner', {
            dataSpinnerSize: 16


        });
    };

    // Loading button
    const _componentLoadingButton = function() {
        $('.btn-loading').on('click', function () {
            let btn = $(this),
                initialText = btn.data('initial-text'),
                loadingText = btn.data('loading-text');
            btn.html(loadingText).addClass('disabled');
            setTimeout(function () {
                btn.html(initialText).removeClass('disabled');
            }, 3000)
        });
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _componentLadda();
            _componentLoadingButton();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    Buttons.init();
});

/**
 * Convert a string to slug format
 * @param string
 * @returns {string}
 */
const stringToSlug = (string) => {
    string = string.replace(/^\s+|\s+$/g, ''); // trim
    string = string.toLowerCase();

    // remove accents, swap ñ for n, etc
    let from = "àáäâèéëêìíïîòóöőôùúüűûñç·/_,:;";
    let to = "aaaaeeeeiiiiooooouuuuunc------";
    for (let i = 0, l = from.length; i < l; i++) {
        string = string.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    string = string.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
        .replace(/\s+/g, '-') // collapse whitespace and replace by -
        .replace(/-+/g, '-'); // collapse dashes

    return string;
}

/**
 * DROPZONE
 *
 * Az inputs egy objektumokat tartalmazó tömb, ahol lehet definiálni a type, name (egyben az id is), value, és class értékeket
 * A value-nak azt az értéket add meg, amit majd a response-ból használni akarsz. Pl. url esetén resp.url lesz a value
 * vagy ha nincs resp.value, akkor a value-t simán beteszi, ha mondjuk valamiért fix value kell neki
 * Az első input mindig a kép inputja legyen az old() függvény miatt
 * Példa tömb:
 *  inputs = [{ type: 'text', name: 'ezaneve', value: 'url', classList: 'form-control pl-5' }]
 *  Egyelőre select és társai nem tudom működik-e
 *  TODO: megpróbálni select, checkbox, radio, textarea (van még?) inputokat hozzáadni
 *
 * @param element
 * @param inputs
 * @returns {{previewTemplate: string, dictDuplicateFile: string, paramName: string, dictFileTooBig: string, dictRemoveFile: string, params: {path: string, _token}, error: error, url: string, dictInvalidFileType: string, timeout: number, acceptedFiles: string, preventDuplicates: boolean, success: success, parallelUploads: number, dictResponseError: string, maxFilesize: string, dictMaxFilesExceeded: string, uploadMultiple: string, removedfile: removedfile, maxFiles: (string|number), dictDefaultMessage: string}}
 */
// const dzOptions = (element, inputs) => {
//     return {
//         inputs: inputs,
//         url: element.dataset.url,
//         paramName: "file", // The name that will be used to transfer the file
//         dictDefaultMessage: 'Húzz ide fájlokat <span>vagy KATTINTS IDE a feltöltéshez!</span>',
//         dictFileTooBig: 'A fájl mérete meghaladja a megengedett ' + element.dataset.maxfilesize + ' MB-ot',
//         dictRemoveFile: 'Mégsem',
//         dictInvalidFileType: 'Csak jpg vagy png képet tölthet fel',
//         dictMaxFilesExceeded: 'Csak ' + element.dataset.maxfiles + ' képet tölthetsz fel!',
//         dictResponseError: 'A feltöltés sikertelen, próbáld újra!',
//         dictDuplicateFile: 'Ezt a fájlt már feltöltötted',
//         maxFilesize: element.dataset.maxfilesize,
//         maxFiles: element.dataset.maxfiles ?? 999,
//         timeout: 300000,
//         acceptedFiles: element.dataset.acceptedfiles,
//         uploadMultiple: element.dataset.uploadmultiple,
//         parallelUploads: 1,
//         preventDuplicates: true,
//         params: {
//             _token: document.querySelector('input[name="_token"]').value,
//             path: element.dataset.path
//         },
//         previewTemplate: element.dataset.uploadmultiple ? document.querySelector('#tpl-multiple').innerHTML : document.querySelector('#tpl-single').innerHTML,
//         removedfile: function (file) {
//             file.previewElement.remove();
//             if (!this.options.uploadMultiple) {
//                 let children = [...this.element.childNodes];
//                 children.forEach(child => {
//                     if (child.classList && child.classList.contains('dz-message')) {
//                         child.classList.remove('d-none');
//                     }
//                 });
//             }
//         },
//         success: function (file, resp) {
//             console.log('success');
//             new PNotify({
//                 title: resp.message ?? resp[0].message,
//                 icon: 'icon-checkmark3',
//                 type: 'success'
//             });
//             // console.log(this.options.getAppendable());
//             //file.previewElement.append(this.options.getAppendable());
//             // console.log(file.previewElement);
//             // console.log(this.options.previewTemplate);
//             //
//             // inputs.forEach(input => {
//             //     let inputName = this.options.uploadMultiple ? input.name + '[]' : input.name;
//             //     let el = document.querySelector(`input[name="${inputName}"]`);
//             //     input.value ? el.value = (resp[input.value] ?? resp[0][input.value]) : null;
//             // });
//
//             if (!this.options.uploadMultiple) {
//                 let children = [...this.element.childNodes];
//                 children.forEach(child => {
//                     if (child.classList && child.classList.contains('dz-message')) {
//                         child.classList.add('d-none');
//                     }
//                 });
//             }
//         },
//         addedfiles: function (files) {
//             console.log(files);
//             console.log(this);
//             this.element.append(this.options.getAppendable(files));
//             // this.options.getAppendable(files);
//             // console.log(this.options.getAppendable());
//         },
//         error: function (file, resp, xhr) {
//             file.previewElement.remove();
//             if (resp.errors && resp.errors.file.length > 0) {
//                 new PNotify({
//                     title: resp.errors.file[0],
//                     icon: 'icon-blocked',
//                     type: 'error'
//                 });
//             } else {
//                 new PNotify({
//                     title: resp,
//                     icon: 'icon-blocked',
//                     type: 'error'
//                 });
//             }
//             this.element.classList.remove('dz-started', 'dz-clickable');
//         },
//         getAppendable: function (files) {
//             const appendable = document.querySelector('.dz-input-fields');
//             inputs.forEach(input => {
//                 let el = document.createElement('input');
//                 el.type = input.type ?? 'hidden';
//                 el.name = input.name ?? 'name';
//                 el.id = el.name;
//                 if (element.dataset.uploadmultiple) {
//                     el.name = el.name + '[]';
//                 }
//
//                 // input.value ? el.value = input.value : null;
//                 input.classList ? el.classList = input.classList : null;
//                 appendable.append(el);
//             });
//             return appendable;
//         }
//     }
// }
//
// Dropzone.autoDiscover = false;

/**
 * Detect form changes before navigation
 *
 * @type {boolean}
 */
let saving = false;
const form = document.getElementById('form');

// form being updated
if (form) {
    form.onsubmit = function() { saving = true; };
}
// form not saved warning
// window.addEventListener('beforeunload', function (e) {
//     if (!saving) {
//         const f = FormChanges(form);
//         if (f && f.length > 0) {
//             // Cancel the event
//             e.preventDefault(); // If you prevent default behavior in Mozilla Firefox prompt will always be shown
//             // Chrome requires returnValue to be set
//             e.returnValue = '';
//         }
//     }
// });

/**
 * Show changed messages
 * @constructor
 */
function DetectChanges() {
    let f = FormChanges(form), msg = "";
    for (let e = 0, el = f.length; e < el; e++) msg += "\n    #" + f[e].id;
    alert((msg ? "Elements changed:" : "No changes made.") + msg);
}

/**
 * Create appendAfter functionality
 * newElement.appendAfter(element);
 */
Element.prototype.appendAfter = function (element) {
    element.parentNode.insertBefore(this, element.nextSibling);
}, false;

/**
 * Submit form with validation
 *
 * @param form
 * @param e
 * @returns {Promise<void>}
 */
const postData = async (form, e) => {
    e.preventDefault();
    window.dispatchEvent(new CustomEvent('toggle-loader'));
    // Clear all error spans
    let errorSpans = [... document.querySelectorAll('.text-danger')];
    errorSpans.forEach(span => {
        span.remove();
    });
    // 1. Setup the request
    // ================================
    // 1.1 Headers
    const headers = new Headers();
    // Tell the server we want JSON back
    headers.set('Accept', 'application/json');

    // 1.2 Form Data
    // We need to properly format the submitted fields.
    // Here we will use the same format the browser submits POST forms.
    // You could use a different format, depending on your server, such
    // as JSON or XML.
    let formData = new FormData(form);
    // 2. Make the request
    // ================================
    const action = e.target.action;
    const result = fetch(action, {
        method: e.target.method,
        headers,
        body: formData
    });

    // 3. Use the response
    // ================================
    result
        // 3.1 Convert the response into JSON-JS object.
        .then(function(response) {
            return response.json();
            // return response.json();
        })
        // 3.2 Do something with the JSON data
        .then(function(jsonData) {
            window.dispatchEvent(new CustomEvent('toggle-loader'));
            if (jsonData.errors && Object.keys(jsonData.errors).length > 0) {
                let elements = [];
                for (let key in jsonData.errors) {
                    let span = document.createElement('span');
                    span.classList.add('form-text', 'text-danger');
                    span.innerText = jsonData.errors[key];
                    let element = document.querySelector(`input[name='${key}']`) ??
                        document.querySelector(`textarea[name='${key}']`) ?? document.querySelector(`select[name='${key}']`);
                    if (!element) {
                        // Lehet, hogy fájlfeltöltés
                        element = document.getElementById(`${key}`);
                    }
                    element ? span.appendAfter(element) : null;
                    elements = [...elements, element];
                    // console.log(element, element.offsetTop);
                }
                new PNotify({
                    title: 'Hibák vannak az űrlapban. Kérlek nézd át és javítsd!',
                    icon: 'icon-blocked',
                    type: 'error'
                });
                elements[0].scrollIntoView();
            } else if (jsonData.exception) {
                new PNotify({
                    title: jsonData.message,
                    icon: 'icon-blocked',
                    type: 'error'
                });
            } else {
                saving = true;
                location.assign(jsonData.return_url);
            }
        })
        .catch(function (err) {
            console.error(err);
            window.dispatchEvent(new CustomEvent('toggle-loader'));
        });
};

/**
 * Hide the plus icons is sidebar
 *
 * @type {Element}
 */
const sideBarToggle = document.querySelector('.sidebar-main-toggle');
sideBarToggle.onclick = () => {
    const addButtons = [... document.querySelectorAll('.nav-link-add')];
    addButtons.forEach((btn) => {
        if (btn.classList.contains('d-none')) {
            btn.classList.remove('d-none');
        } else {
            btn.classList.add('d-none');
        }
    })
}
