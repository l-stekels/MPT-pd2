import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['input', 'placeholder', 'preview', 'previewClearButton', 'previewFilename'];

  connect() {
    this.clear();
    this.previewClearButtonTarget.addEventListener('click', () => this.clear());
    this.inputTarget.addEventListener('change', (event) => this.onInputChange(event));
  }

  clear() {
    this.inputTarget.value = '';
    this.inputTarget.style.display = 'block';
    this.placeholderTarget.style.display = 'block';
    this.previewTarget.style.display = 'none';
    this.previewFilenameTarget.textContent = '';
  }

  onInputChange(event) {
    const files = Array.from(event.target.files);
    if (typeof files[0] === 'undefined') {
      return;
    }
    // this.inputTarget.style.display = 'none';
    this.placeholderTarget.style.display = 'none';
    this.previewTarget.style.display = 'block';
    files.forEach(file => {
      const previewContainer = this.previewFilenameTarget.cloneNode(true)
      previewContainer.textContent = file.name;
      this.previewTarget.appendChild(previewContainer);
    })
  }
}
