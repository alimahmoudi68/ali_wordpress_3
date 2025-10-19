document.addEventListener("alpine:init", () => {
    Alpine.data("dropdown", () => ({
      open: false,
      close() {
        if (this.open) {
          this.filtersTemp = JSON.parse(JSON.stringify(this.filters));
          this.open = false;
        }
      },
      toggle() {
        this.filtersTemp = JSON.parse(JSON.stringify(this.filters));
        this.open = !this.open;
      },
    }));
  
    Alpine.data("allPost", () => ({
      url: `${window.portfolioData.home_url}/portfolio?`,
      filterElementKey: window.portfolioData.filterElKeyObj,
      filtersTemp: window.portfolioData.filters,
      filters: window.portfolioData.filters,
      allAttributes: window.portfolioData.product_attributes,
      loading: false,
      page: 1,
      totalPage: Math.ceil(
        window.portfolioData.total_posts / window.portfolioData.limit
      ),
      posts: window.portfolioData.posts,
      showDetail: false,
      detailData: {},
      loaded: false,
  
      init() {
        this.loaded = true;
      },
  
      fetchPosts(query, p) {
        if (this.loading) return;
        this.loading = true;
  
        if (p === 1) {
          this.posts = [];
        } else {
          query = `page=${p}${query.length > 0 ? "&" : ""}${query}`;
        }
  
        fetch(
          `${window.portfolioData.home_url}/wp-json/myapi/v1/portfolios?page=${p}${
            query !== "" ? "&" + query : ""
          }`
        )
          .then((res) => res.json())
          .then((data) => {
            if (data.portfolios.length > 0) {
              this.posts = p === 1 ? data.portfolios : this.posts.concat(data.portfolios);
              this.page = p;
              this.totalPage = Math.ceil(data.total / window.portfolioData.limit);
            } else {
              this.totalPage = 0;
            }
            this.loading = false;
            window.history.pushState({}, "", this.url + query);
          })
          .catch((err) => {
            console.error("Error fetching posts:", err);
            this.loading = false;
          });
      },
  
      makeFilterUrl(nextPage) {
        this.filters = { ...this.filtersTemp };
  
        const query = Object.entries(this.filters)
          .filter(([_, vals]) => vals.length > 0)
          .map(([k, v]) => `${k}=${v.join(",")}`)
          .join("&");
  
        this.fetchPosts(query, nextPage);
        this.open = false;
      },
  
      filterToList(obj) {
        const result = [];
        for (const key in obj) {
          obj[key].forEach((val) => result.push({ key, value: val }));
        }
        return result;
      },
  
      isShowFilterElement(filterName, attributeName) {
        return attributeName.includes(this.filterElementKey[filterName]);
      },
  
      updateFilters(id, type, isMakeFilterUrl = false) {
        const list = this.filtersTemp[type];
        const exists = list.includes(id);
        this.filtersTemp[type] = exists
          ? list.filter((i) => i !== id)
          : [...list, id];
  
        if (isMakeFilterUrl) {
          this.makeFilterUrl(1);
        }
      },
  
      clearFilter(type) {
        this.filtersTemp[type] = [];
      },
  
      getAttribiteName(attribute, term) {
        const attrList = this.allAttributes[attribute] || [];
        const found = attrList.find((x) => x.key === term);
        return found ? found.value : term;
      },
  
      showDetailHandler(post) {
        this.detailData = post;
        this.showDetail = true;
      },
  
      closeDetailHandler() {
        this.showDetail = false;
        this.detailData = {};
      },
    }));
  });
  