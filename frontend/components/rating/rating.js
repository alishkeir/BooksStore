import { useState, useEffect } from 'react';
import { useMutation } from 'react-query';
import { handleApiRequest } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';
import { useDispatch } from 'react-redux';
import { updateSidebar } from '@store/modules/ui';
import { Numbers, RatingWrapper, Star, StarWrapper, Stars } from '@components/rating/rating.styled';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'rating-update': {
      method: 'PUT',
      path: '/profile/reviews',
      ref: 'customerReviews',
      request_id: 'rating-update',
      body: {
        product_id: null,
        review: null,
      },
    },
  },
};

export default function Rating(props) {
  let { globalRating, userRating: userRatingProp = 0, count, numbers, productId, user } = props;

  let dispatch = useDispatch();
  let [userRating, setUserRating] = useState(userRatingProp);
  let [hovering, setHovering] = useState(false);
  let [currentHover, setCurrentHover] = useState(null);

  let ratingUpdateQuery = useMutation('rating-update', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild));

  let requestRatingGet = useRequest(requestTemplates, ratingUpdateQuery);
  requestRatingGet.addRequest('rating-update');

  useEffect(() => {
    if (!userRatingProp) return;

    setUserRating(userRatingProp);
  }, [userRatingProp]);

  return (
    <RatingWrapper>
      <Stars onMouseLeave={handleStarsMouseLeave}>
        {new Array(5).fill(null).map((value, index) => {
          let indexPlusOne = index + 1;
          let isOn = hovering
            ? indexPlusOne <= currentHover
              ? true
              : false
            : indexPlusOne <= (userRating ? userRating : globalRating)
            ? true
            : false;
          return (
            <StarWrapper
              key={indexPlusOne}
              isOn={isOn}
              hovering={hovering}
              userRating={userRating}
              onMouseEnter={() => handleMouseEnter(indexPlusOne)}
              onClick={() => handleRatingSelect(indexPlusOne)}
            >
              <Star></Star>
            </StarWrapper>
          );
        })}
      </Stars>
      {numbers && (
        <Numbers>
          <strong>{globalRating}</strong> ({count})
        </Numbers>
      )}
    </RatingWrapper>
  );

  function handleRatingSelect(index) {
    if (!user) {
      dispatch(updateSidebar({ open: true, type: 'login' }));
    } else {
      setUserRating(index);

      requestRatingGet.modifyHeaders((currentHeader) => {
        currentHeader['Authorization'] = `Bearer ${user.token}`;
      });

      requestRatingGet.modifyRequest('rating-update', (currentRequest) => {
        currentRequest.method = userRating ? 'PUT' : 'POST';
        currentRequest.body.product_id = productId;
        currentRequest.body.review = index;
      });

      requestRatingGet.commit();
    }
  }

  function handleMouseEnter(star) {
    if (currentHover != star) setCurrentHover(star);
    if (!hovering) setHovering(true);
  }

  function handleStarsMouseLeave() {
    if (currentHover) setCurrentHover(null);
    if (hovering) setHovering(false);
  }
}
