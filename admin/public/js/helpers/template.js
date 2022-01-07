/**
 * Load templates of lodash dynamically
 * @global
 * @param {string} url relative path of template file
 * @returns {function} _.template with template content
 */

var Template = {
    cache: true,
    location: '/',
    setLocation: function (location) {
        this.location = location;
    },

    setCache: function (active) {
        this.cache = active;
    },

    load: function (file) {
        const self = this;
        if (!self.loaded) {
            self.loaded = {};
        }
        const url = self.makeUrl(file);
        if (!self.cache || !self.loaded[url]) {
            const hash = self.cache ? '' : '&' + Math.random()
            $.ajax(url + '?' + window.app_version + hash, {
                method: 'get',
                async: false,
                contentType: 'text',
                success: function (result) {
                    self.loaded[url] = _.template(result);
                },
                error: function (result) {
                    console.error(result);
                }
            });
        }
        return self.loaded[url];
    },

    makeUrl: function (file) {
        let url = [this.location];
        const end_slash = _.endsWith(this.location, '/');
        const start_slash = _.startsWith(file, '/');

        if (!end_slash && !start_slash)
            url.push('/');

        url.push(file);

        if (!_.endsWith(file, '.tpl'))
            url.push('.tpl');

        return url.join('');
    }
}
