var bcrypt = require('bcrypt');

const saltRounds = 10;
const phash = (key)=>{
    return new Promise((ok,fail)=>{

        bcrypt.hash(key, saltRounds, function(err, hash) {
            if(err){
            return fail(err);
            }
            ok(hash);

        });
    });
};

(async function(){

    console.log(process.argv[2]);
    var hash = await phash(process.argv[2]);
    
    console.log(hash);

})();


