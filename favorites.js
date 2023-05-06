let promptsID = document.querySelectorAll("#btnFavorites");
promptsID.forEach(function (btn) {
    btn.addEventListener("click", function () {
        let postid = this.dataset.postid;
        console.log(postid);

        //change text of prompt that is favorited
        
        if (this.innerHTML == "Add to favorites") {
            
            
            this.innerHTML = "Remove from favorites";
        }
        else {
            this.innerHTML = "Add to favorites";
            
        }
        
    });
});









