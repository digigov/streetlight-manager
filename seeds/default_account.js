
var bcrypt = require('bcrypt');


exports.seed = function(knex, Promise) {
  // Deletes ALL existing entries
  return Promise.resolve(true).then(function () {
      // Inserts seed entries
      const saltRounds = 10;
      
      return new Promise((ok,fail)=>{

        bcrypt.hash("adminadmin", saltRounds, function(err, hash) {
          if(err){
            return fail(err);
          }
          ok(hash);

        });
      });
    
    
    }).then(pwd=>{
      return knex('account').insert([
        {acc:"admin",pwd:pwd},
      ]);
    });
};
