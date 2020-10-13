#!/bin/sh

tmpdir=$(mktemp -d)


# Download source file
if [ -n "$1" ]; then
    uscan_opts="--download-version=$1"
fi
#uscan --noconf --force-download --no-symlink --verbose --destdir=$tmpdir $uscan_opts

cd $tmpdir

# Other method to download (comment uscan if you use this)
wget http://www.DigitalProspects.org/files/stable/standard/DigitalProspects-3.5.4.tgz

# Rename file to add +dfsg
tgzfile=$(echo *.tgz)
version=$(echo "$tgzfile" | perl -pi -e 's/^DigitalProspects-//; s/\.tgz$//; s/_/./g; s/\+nmu1//; ')

cd - >/dev/null

mv $tmpdir/DigitalProspects-${version}.tgz ../
echo "File ../DigitalProspects-${version}.tgz is ready for git-import-orig"

rm -rf $tmpdir
