export default async function (app) {


	app
		.setPath(`@src`, `resources`)
        .setPath('@dist',process.env.WORDPRESS_PATH+"/wp-content/uploads/wpwhales/assets")
        .hash()
		.entry({
			app: 'scripts/app/app.js',
		}).assets([{from:app.path('@src/scripts/misc'), to:'misc'}])
	if(app.mode==="production"){
		console.log("PRODUCTION MINIMIZE & split chunks")
		app.splitChunks();
		app.minimize();
	}

}
