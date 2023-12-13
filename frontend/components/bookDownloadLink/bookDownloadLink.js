import { useMutation } from 'react-query';
import { getSiteCode } from '@libs/site';
import useUser from '@hooks/useUser/useUser';
import { DownloadLinkWrapper } from '@components/bookDownloadLink/bookDownloadLink.styled';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

export default function BookDownloadLink({ id, size, type = '', children }) {
  let settings = settingsVars.get(url.getHost());

  let { actualUser } = useUser();

  let downloadQuery = useMutation(() => {
    let contentType = '';
    let contentDisposition = `attachment; filename="book.${type}"`;
    let filename = 'book';

    fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/${getSiteCode(settings.key)}/composite`, {
      method: 'POST',
      headers: {
        Accept: 'application/json; charset=utf-8',
        'Content-type': 'application/json; charset=utf-8',
        Authorization: `Bearer ${actualUser.token}`,
      },
      body: JSON.stringify({
        request: [
          {
            method: 'POST',
            path: '/profile/download',
            ref: 'downloadEbook',
            request_id: 'download_book',
            body: {
              type: type,
              ebook_id: id,
            },
          },
        ],
      }),
    })
      .then((response) => {
        if (!response.ok) throw new Error(`API response: ${response.status}`);

        contentDisposition = response.headers.get('content-disposition');
        contentType = response.headers.get('content-type');
        filename = contentDisposition ? contentDisposition.match(/filename="(.+)"/)[1] : contentDisposition;

        return response.blob();
      })
      .then((data) => {
        import('downloadjs').then((module) => {
          let download = module.default;
          download(data, filename, contentType);
        });
      })
      .catch((error) => console.log(error));
  });

  function handleDownloadClick() {
    if (!actualUser) return;
    downloadQuery.mutate();
  }

  return (
    <DownloadLinkWrapper onClick={handleDownloadClick}>
      {children} {size && `(${size} kb)`}
    </DownloadLinkWrapper>
  );
}
