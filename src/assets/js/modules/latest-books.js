export default class LatestBooks {
  constructor() {
    this.container = document.querySelector('.latest-books');

    if (!this.container) return;

    this.list = this.container.querySelector('.latest-books-list');
    this.itemsContainer = this.container.querySelector('.latest-books-list-items');

    this.init();
  }

  init() {
    this.fetchBooks();
  }

  fetchBooks() {
    const { homeUrl, postId } = window.foozThemeVars;
    const url = new URL(`${homeUrl}/wp-json/fooz-theme/v1/latest-books`);

    if (postId) {
      url.searchParams.set('exclude', postId);
    }

    fetch(url.toString())
      .then(response => response.json())
      .then(data => this.renderBooks(data))
      .catch(() => this.renderError());
  }

  renderBooks(books) {
    this.list.classList.remove('is-loading');

    if (!books.length) {
      this.itemsContainer.innerHTML = '<p>No books found.</p>';
      return;
    }

    this.itemsContainer.innerHTML = books.map(book => this.bookTemplate(book)).join('');
  }

  bookTemplate({ title, date, genre, excerpt, url }) {
    return `
      <a class="latest-books-list-item" href="${url}">
        <h3 class="latest-books-list-item-title">${title}</h3>
        ${genre ? `<p class="latest-books-list-item-genre">Genre: ${genre}</p>` : ''}
        <time class="latest-books-list-item-date" datetime="${date}">${date}</time>
        ${excerpt ? `<p class="latest-books-list-item-excerpt">${excerpt}</p>` : ''}
      </a>
    `;
  }

  renderError() {
    this.list.classList.remove('is-loading');
    this.itemsContainer.innerHTML = '<p>Failed to load books. Please try again later.</p>';
  }
}
