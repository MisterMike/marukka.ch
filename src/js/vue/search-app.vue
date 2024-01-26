import axios from 'axios';

// Information needed for connecting to our CraftQL endpoint
const apiToken = 'ksapnidoEidTrK-_aWYvBbcv64EXt0Vp';
const apiUrl = '/api';

// What to search for
const searchSections = ['pages'];
const searchPrefix = 'title:';

// The query to search for entries in Craft
const searchQuery =
  `
  query searchQuery($sections: [String!], $needle: String!, $limit: Int)
  {
    entries(section: $sections, search: $needle, limit: $limit) {
      title
      url
    }
  }
`;

const configureApi = (url, token) => {
  return {
    baseURL: url,
    headers: {
      'Authorization': `Bearer ${token}`,
      'X-Requested-With': 'XMLHttpRequest'
    }
  };
};

const searchApi = axios.create(configureApi(apiUrl, apiToken));

// Execute a GraphQL query by sending an XHR to our api endpoint
const executeQuery = (api, query, variables, callback) => {
api.post('', {
query: query,
variables: variables
}).then((result) => {
if (callback) {
callback(result.data);
}
console.log(result.data);
}).catch((error) => {
console.log(error);
})
};

export default {

data: function() {
return {
searchApi: axios.create(configureApi(apiUrl, apiToken)),
searchQuery: '',
searchResults: {}
}
},
methods: {

// Perform a search
performSearch() {
// If they haven't entered anything to search for, return nothing
if (this.searchQuery === '') {
this.searchResults = {};
return;
}
// Set the variables we will pass in to our query
const variables = {
sections: searchSections,
needle: searchPrefix + this.searchQuery,
limit: 5
};
// Execute the query
executeQuery(searchApi, searchQuery, variables, (data) => {
this.searchResults = data.data.entries;
});
}
},
};