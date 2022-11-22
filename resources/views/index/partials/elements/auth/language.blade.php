<select onchange="location = '/admin/localization/' + this.value;">
    <option value="ru" <?php if ( App::getLocale() == "ru" ) { echo "selected"; } else {echo "";}?>>RU</option>
    <option value="en" <?php if ( App::getLocale() == "en" ) { echo "selected"; } else {echo "";}?>>EN</option>
    <option value="ua" <?php if ( App::getLocale() == "ua" ) { echo "selected"; } else {echo "";}?>>UA</option>
    <option value="pl" <?php if ( App::getLocale() == "pl" ) { echo "selected"; } else {echo "";}?>>PL</option>
</select>
