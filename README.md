# Article Api 


# Endpoints

### get /api/article - view articles

###  get /api/articles/{id} - view an article
		id - Article Id
		
### post /api/articles/{id}/comment - add a comment
			id - Article Id
			body - {
				name: string,
				comment: string
			}
			
### get /api/articles/{id}/view - add a view to article
			id - Article Id
			
### get  /api/articles/{id}/like - add a like to article
			id - Article Id

## init
Run npm install
run composer install
seed db - php artisan db:seed
add swagger - php artisan l5-swagger:generate
