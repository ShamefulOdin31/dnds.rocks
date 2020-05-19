<nav class="navbar navbar-expand-sm navbar-dark">
    <form class="form-inline my-2 my-lg-0 ml-auto" action="search.php" method="post">
        <select name="type" id="type" class="form-control">
            <option value="catname">Categories</option>
            <option value="cname">Characters</option>
            <option value="spellName">Spells</option>
        </select>
        <input class="form-control" type="search" placeholder="Search" aria-label="Search" name="search">
        <button class="btn btn-primary" type="submit">Search</button>
    </form>
</nav>