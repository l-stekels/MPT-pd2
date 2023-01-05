import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['tablist', 'tabcontent'];
  tabElements = [];
  tabContents = [];

  connect() {
    this.tabElements = this.tablistTarget.children;
    this.tabContents = this.tabcontentTarget.children;
    for (const element of this.tabElements) {
      element.addEventListener('click', event => this.onTabClick(event, element))
    }
  }

  onTabClick(event, parent) {
    event.preventDefault();
    // Find the one that is active and make it inactive
    for (const listItem of this.tabElements) {
      listItem.classList.remove('active');
    }
    parent.classList.add('active');
    const target = event.target.getAttribute('aria-controls');
    for (const item of this.tabContents) {
      if (target === item.id) {
        item.classList.add('active')
      } else {
        item.classList.remove('active')
      }
    }
  }
}
