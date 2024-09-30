
SCRIPTPATH="$( cd -- "$(dirname "$0")" >/dev/null 2>&1 ; pwd -P )"
REPODIR=$SCRIPTPATH/public_html

echo "Clone to $REPODIR"
git clone git@github.com:clarumedia/relator-core.git $REPODIR

#Prevent file permissions changes from being tracked
cd $REPODIR
git config core.filemode false

chmod -R 755 $REPODIR
